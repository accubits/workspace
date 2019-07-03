import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { KraModulesComponent } from './kra-modules.component';

describe('KraModulesComponent', () => {
  let component: KraModulesComponent;
  let fixture: ComponentFixture<KraModulesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ KraModulesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(KraModulesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
