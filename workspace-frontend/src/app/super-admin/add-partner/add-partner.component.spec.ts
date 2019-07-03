import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddPartnerComponent } from './add-partner.component';

describe('AddPartnerComponent', () => {
  let component: AddPartnerComponent;
  let fixture: ComponentFixture<AddPartnerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddPartnerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddPartnerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
