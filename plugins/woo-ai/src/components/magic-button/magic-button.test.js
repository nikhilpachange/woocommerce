import { render, fireEvent } from '@testing-library/react';
import { MagicButton } from './magic-button';

describe('MagicButton', () => {
  const renderButton = (props = {}) => render(<MagicButton label="Test Button" {...props} />);

  it('renders correctly', () => {
    const { getByText } = renderButton();
    expect(getByText('Test Button')).toBeInTheDocument();
  });

  it('calls onClick prop when clicked', () => {
    const handleClick = jest.fn();
    const { getByText } = renderButton({ onClick: handleClick });

    fireEvent.click(getByText('Test Button'));
    expect(handleClick).toHaveBeenCalledTimes(1);
  });
});
