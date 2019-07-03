import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CheckboxesResponseComponent } from './checkboxes-response.component';

describe('CheckboxesResponseComponent', () => {
  let component: CheckboxesResponseComponent;
  let fixture: ComponentFixture<CheckboxesResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CheckboxesResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CheckboxesResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
