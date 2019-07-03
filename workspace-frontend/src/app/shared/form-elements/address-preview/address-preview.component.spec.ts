import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddressPreviewComponent } from './address-preview.component';

describe('AddressPreviewComponent', () => {
  let component: AddressPreviewComponent;
  let fixture: ComponentFixture<AddressPreviewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddressPreviewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddressPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
