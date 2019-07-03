import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DropdownSubmitComponent } from './dropdown-submit.component';

describe('DropdownSubmitComponent', () => {
  let component: DropdownSubmitComponent;
  let fixture: ComponentFixture<DropdownSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DropdownSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DropdownSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
