import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PartnerNavComponent } from './partner-nav.component';

describe('PartnerNavComponent', () => {
  let component: PartnerNavComponent;
  let fixture: ComponentFixture<PartnerNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PartnerNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PartnerNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
