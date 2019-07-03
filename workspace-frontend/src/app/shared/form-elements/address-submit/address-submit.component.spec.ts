import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddressSubmitComponent } from './address-submit.component';

describe('AddressSubmitComponent', () => {
  let component: AddressSubmitComponent;
  let fixture: ComponentFixture<AddressSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddressSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddressSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
